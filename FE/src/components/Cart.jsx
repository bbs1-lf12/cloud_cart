import { useContext } from 'react';
import Modal from './UI/Modal.jsx';
import CartContext from '../store/CartContext.jsx';
import Button from './UI/Button.jsx';
import { currencyFormatter } from '../util/formatting.js';
import UserProgressContext from '../store/UserProgressContext.jsx';
import CartItem from './CartItem.jsx';

export default function Cart() {
	const cartCtx = useContext(CartContext);
	const userProgressCtx = useContext(UserProgressContext); //UserProgressContext enthält Informationen über den Fortschritt des Benutzers, 
	//z.B.ob der Warenkorb geöffnet oder geschlossen ist.
	
	const cartTotal = cartCtx.items.reduce(
		(totalPrice, item) => totalPrice + item.quantity * item.price, 
		0
		//multipliziert die Menge mit dem Preis jedes Artikels. 0 ist der Startwert der Reduktion.
	);

	function handleCloseCart() {
		userProgressCtx.hideCart();
	}

	return (
		<Modal className="cart" open={userProgressCtx.progress === 'cart'}>
			<h2>Your Cart</h2>
			<ul>
				{cartCtx.items.map((item) => ( // callback funktion auf Array
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
			<p className="cart-total">{currencyFormatter.format(cartTotal)}</p>
			<div className="modal-actions">
				<Button textOnly onClick={handleCloseCart}>Close</Button>
				<Button onClick={handleCloseCart}>Go to Checkout</Button>
			</div>
		</Modal>
	);
}
