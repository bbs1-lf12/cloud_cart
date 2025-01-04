import {useContext} from "react"
import {centsToPrice, currencyFormatter} from "../util/formatting"
import Button from "./UI/Button.jsx"
import CartContext from "../store/CartContext.jsx";

export default function ProductItem({product}) {
  const cartCtx = useContext(CartContext);

  function handleAddProductToCart() {
    const productToAdd = {
      id: product.id,
      name: product.title, 
      price: product.priceInCents, 
      quantity: 1,
    };
    cartCtx.addItem(productToAdd);
  }  

  return <li className="product-item">
    <article>
      <img src={`http://localhost:8080/images/${product.image}`} alt={product.title}/>
      <div>
        <h3>{product.title}</h3>
        <p className="product-item-description">{product.description}</p>
        <p className="product-item-price">{currencyFormatter.format(centsToPrice(product.priceInCents))}</p>
        <p className="product-item-actions">
          <Button onClick={handleAddProductToCart}> hinzufügen </Button>
        </p>
      </div>

    </article>
  </li>
}
