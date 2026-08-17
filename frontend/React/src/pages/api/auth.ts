
export async function signup(username: string, email: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({ username, email, password})
    });

    return response.json();
}