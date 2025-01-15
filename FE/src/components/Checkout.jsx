/* import { useContext } from 'react';
import CartContext from '../store/CartContext.jsx';
import Button from './UI/Button.jsx';
import { currencyFormatter } from '../util/formatting.js';
import { PayPalButtons } from '@paypal/react-paypal-js';


export default function Checkout() {
    const cartCtx = useContext(CartContext);
    const cartTotal = cartCtx.items.reduce(
        (totalPrice, item) => totalPrice + item.quantity * item.price,
        0
    );

    function handlePayment() {
        alert('Payment successful! Thank you for your purchase.');
        cartCtx.clearCart(); // Make sure this method exists in CartContext
    }

    return (
        <div className="checkout">
            <h2>Checkout</h2>
            <ul>
                {cartCtx.items.map((item) => (
                    <li key={item.id}>
                        {item.name} x {item.quantity} - {currencyFormatter.format(item.price)}
                    </li>
                ))}
            </ul>
            <p>Total: {currencyFormatter.format(cartTotal)}</p>
            <Button onClick={handlePayment}>Pay Now</Button>
        </div>
    );
}    */
 
import { useContext, useState } from 'react';
import { PayPalButtons, usePayPalScriptReducer } from '@paypal/react-paypal-js';
import CartContext from '../store/CartContext.jsx';
import Button from './UI/Button.jsx';
import { currencyFormatter } from '../util/formatting.js';

export default function Checkout() {
    const cartCtx = useContext(CartContext);
    const cartTotal = cartCtx.items.reduce(
        (totalPrice, item) => totalPrice + item.quantity * item.price,
        0
    );

    const [{ isPending }] = usePayPalScriptReducer();
    const [currency, setCurrency] = useState('USD');

    const createOrder = (data, actions) => {
        return actions.order.create({
            purchase_units: [
                {
                    amount: {
                        value: cartTotal.toFixed(2), // Using the total from the cart
                        currency_code: currency,
                    },
                },
            ],
        });
    };

    const onApprove = (data, actions) => {
        return actions.order.capture().then((details) => {
            const name = details.payer.name.given_name;
            alert(`Transaction completed by ${name}`);
            cartCtx.clearCart(); // Clear the cart after successful payment
        });
    };

    const onCurrencyChange = (event) => {
        setCurrency(event.target.value);
        // Additional logic to reload PayPal SDK with new currency can be added here
    };

    return (
        <div className="checkout">
            <h2>Checkout</h2>
            <ul>
                {cartCtx.items.map((item) => (
                    <li key={item.id}>
                        {item.name} x {item.quantity} - {currencyFormatter.format(item.price)}
                    </li>
                ))}
            </ul>
            <p>Total: {currencyFormatter.format(cartTotal)}</p>

            <label htmlFor="currency">Select Currency: </label>
            <select id="currency" value={currency} onChange={onCurrencyChange}>
                <option value="USD">United States Dollar</option>
                <option value="EUR">Euro</option>
                {/* Add more currency options as needed */}
            </select>

            {isPending && <div className="spinner">Loading PayPal...</div>}

            <PayPalButtons
                style={{ layout: 'vertical' }}
                createOrder={createOrder}
                onApprove={onApprove}
            />

            <Button onClick={() => window.location.replace("https://stackoverflow.com")}>Pay Now</Button>
        </div>
    );
}
