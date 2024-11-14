import { currencyFormatter } from "../util/formatting"

export default function CartItem({ name, quantity, price, onIncrease, onDecrease  }) { //als atribut wäre auch item ausreichend
    
    
    
    return (
        <li className="cart-item">
            <p> 
                {name} - {quantity} X {currencyFormatter.format(price) }
            </p>
            <p className="cart-item-actions">
                <button onClick={onDecrease}>-</button>
                <span>{quantity}</span>
                <button onClick={onIncrease}>+</button>
            </p>
        </li>
    );
}