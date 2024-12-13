import React, { useState } from "react";
import axios from "axios";


export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    const [error, setError] = useState("");

    const handleSubmit = async (e) => {
      /*   e.preventDefault(); // stoppt den refresh der seite 
        try {               // ein block für die erfolgrreiche login-logik
            const response = await axios.post("http://your-api-url/login", { email, password }); // await axios.post führt eine post anfrage 
            // an der angegebene API-Endpunkt es übergibt email und passwort als Daten und wartet auf die antwort von den Server.
            console.log("Login successful:", response.data);
        } catch (error) {
            console.error("Login failed:", error);
        } */
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
