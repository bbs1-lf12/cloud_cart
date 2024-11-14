// Button-Komponente wird importiert, um einen wiederverwendbaren Button zu haben.
import Button from './UI/Button.jsx';  
// Das Logo-Bild wird importiert, um es später im Header anzuzeigen.
import logoImg from '../assets/logo-transparent-png.png';
import shoppingCart from '../assets/shopping-cart.png';  
import { useContext } from 'react';
import CartContext from '../store/CartContext.jsx';
import UserProgressContext from '../store/UserProgressContext.jsx';
// Die Header-Komponente exportieren, damit sie in anderen Teilen der App verwendet werden kann.
export default function Header() {  
  const cartCtx = useContext(CartContext);
  const userProgressCtx = useContext(UserProgressContext);

  //ERKLärung 
  const totalCartItems = cartCtx.items.reduce((totalNumberOfItems, item) => {
    return totalNumberOfItems + item.quantity;
  }, 0); //ermöglicht ein Array auf einen einzelenen Wert zu reduzieren
  
  function handleShowCart(){
    userProgressCtx.showCart();
  }
  
  return (
    <header id="main-header">
      <div id="title">
        <img src={logoImg} alt="Firmen Logo" /> 
        {/* <h1>Cloud-Cart</h1>   */}
      </div>
      <nav>
          <Button textOnly onClick={handleShowCart}>
            <img className='logo-s' src={shoppingCart} alt="Bild von einem Einkaufskorb"/>
            ({totalCartItems})
						
          </Button>
      </nav>
    </header>
  );
}
/**=======================================================================================================================
 * *                                                     INFO
 *		Anfangs werden immer andere Komponenten oder Bilder, die benötigt werden importiert

 *    Danach wird eine funktion erstellt in die der Inhalt kommt, der Angezeigt wird.
			durch den Befehl export wird sichergestellt, das die Funktion auch woanders aufgerufen werden kann.

 *   textOnly beim Button ist eine Eigenschaft, die in Button.jsx festgelegt wird, sie
		 sagt, dass ........
	
 *	
 *=======================================================================================================================**/ 