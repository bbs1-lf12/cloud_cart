import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header.jsx';
import Products from './components/Products.jsx';
import Cart from './components/Cart.jsx';
import Checkout from './components/Checkout.jsx';
import { CartContextProvider } from './store/CartContext.jsx';
import { UserProgressContextProvider } from './store/UserProgressContext.jsx';

function App() {
  return (
    <UserProgressContextProvider>
      <CartContextProvider>
        <Router>
          <Header />
          <Routes>
            <Route path="/" element={<Products />} />
            <Route path="/checkout" element={<Checkout />} />
          </Routes>
          <Cart />
        </Router>
      </CartContextProvider>
    </UserProgressContextProvider>
  );
}

export default App;


