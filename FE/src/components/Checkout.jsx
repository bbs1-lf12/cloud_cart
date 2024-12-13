import { useContext } from 'react';
import CartContext from '../store/CartContext.jsx';
import Button from './UI/Button.jsx';
import { currencyFormatter } from '../util/formatting.js';

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
}
