
export async function signup(username: string, email: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "signup", username, email, password})
    });

    return response.json();
}

export async function login(username: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "login", username, password})
    });

    return response.json();
}