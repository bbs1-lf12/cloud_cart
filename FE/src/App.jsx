import Header from "./components/Header.jsx";
import Products from "./components/Products.jsx";
import Cart from "./components/Cart.jsx";
import { CartContextProvider } from "./store/CartContext.jsx";
import { UserProgressContextProvider } from "./store/UserProgressContext.jsx";


function App() {
  return (
    <UserProgressContextProvider>  
      <CartContextProvider>
        <Header />
        <Products />
        <Cart />
      </CartContextProvider>
    </UserProgressContextProvider>
  );
}

export default App;
