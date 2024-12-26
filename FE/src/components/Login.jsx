import React, { useState } from "react";
import axios from "axios";
import BEConnectionService from "../services/BEConnectionService.js";
import LocalStorageKeys from "../services/LocalStorageKeys.js";


export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    const [error, setError] = useState("");

    const handleSubmit = async (e) => {
      e.preventDefault();
      try {
        const data = await BEConnectionService.getInstance().login(email, password);
        if (data.token) {
          localStorage.setItem(LocalStorageKeys.LS_API_TOKEN, data.token);
        }
        setIsLoggedIn(true); // fake LogIn
      } catch (error) {
        setError("Login failed");
        console.error(error);
      }
        setIsLoggedIn(true); // fake LogIn
    };

    return (
        isLoggedIn ? <p>Hello {email}</p> : <form onSubmit={handleSubmit}>
            {/* er schaut wenn der User noch nicht eingeloggt ist dann zeigt der das an (das Formular) */}
            <div>
                <label>Email:</label>
                <input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)} // aktualisiert den Zustand email, wenn der Benutzer etwas eintippt.
                    required
                />
            </div>
            <div>
                <label>Password:</label>
                <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
            </div>
            <button type="submit">Login</button>
        </form>
    );
}
