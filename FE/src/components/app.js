import Login from "./Login";

import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={<Login />} />
                {/* Weitere Routen */}
            </Routes>
        </Router>
    );
}

export default App;