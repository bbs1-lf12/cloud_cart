import { useNavigate } from 'react-router-dom';
import { useContext } from 'react';
import CartContext from '../store/CartContext.jsx';
import UserProgressContext from '../store/UserProgressContext.jsx';
import Modal from './UI/Modal.jsx';
import {centsToPrice, currencyFormatter} from '../util/formatting.js';
import Button from './UI/Button.jsx';
import CartItem from './CartItem.jsx';

export default function Cart() {
	const cartCtx = useContext(CartContext);
	const userProgressCtx = useContext(UserProgressContext);
	const navigate = useNavigate();

	const cartTotal = cartCtx.items.reduce(
		(totalPrice, item) => totalPrice + item.quantity * item.price,
		0
	);

	function handleCloseCart() {
		userProgressCtx.hideCart();
	}

	function goToCheckout() {
		userProgressCtx.hideCart();
		navigate('/checkout');
	}
console.log("cart",userProgressCtx)
	return (
		<Modal className="cart" open={userProgressCtx.progress === 'cart'}>
			<h2>Your Cart</h2>
			<ul>
				{cartCtx.items.map((item) => (
					<CartItem
						key={item.id}
						name={item.name}
						quantity={item.quantity}
						price={item.price}
						onIncrease={() => cartCtx.addItem(item)}
						onDecrease={() => cartCtx.removeItem(item.id)}
					/>
				))}
			</ul>
			<p className="cart-total">{currencyFormatter.format(centsToPrice(cartTotal))}</p>
			<div className="modal-actions">
				<Button textOnly onClick={handleCloseCart}>Close</Button>
				<Button onClick={goToCheckout}>Go to Checkout</Button>
			</div>
		</Modal>
	);
}
