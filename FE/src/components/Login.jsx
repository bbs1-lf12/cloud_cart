import React, { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch("http://127.0.0.1:8080/api/login_check", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },

        body: JSON.stringify({ "username": email, "password": password }),
      });

      if (!response.ok) {
        throw new Error("Login failed");
      }

      const data = await response.json();
      if (data.token) {
        // Store the token in localStorage
        localStorage.setItem("JWT_TOKEN", data.token);
        setIsLoggedIn(true);
      } else {
        throw new Error("Token not received");
      }
    } catch (error) {
      setError(error.message || "Login failed");
    }
  };

  const fetchProtectedData = async () => {
    const token = localStorage.getItem("JWT_TOKEN");
    if (!token) {
      setError("No token found, please login again.");
      return;
    }

    try {
      const response = await fetch("/api/protected_resource", {
        method: "GET",
        headers: {
          "Authorization": `Bearer ${token}`,
        },
      });

      if (!response.ok) {
        throw new Error("Failed to fetch protected data");
      }

      const protectedData = await response.json();
      console.log("Protected data:", protectedData);
    } catch (error) {
      setError(error.message || "Failed to fetch protected data");
    }
  };

  return (
    <div>
      {isLoggedIn ? (
        <div>
          <p>Welcome {email}</p>
          <button onClick={fetchProtectedData}></button>
        </div>
      ) : (
        <form onSubmit={handleSubmit}>
          <div>
            <label>Email:</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
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
          {error && <p style={{ color: "red" }}>{error}</p>}
        </form>
      )}
    </div>
  );
}


