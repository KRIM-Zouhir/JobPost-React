
export async function signupapi(username: string, email: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "signup", username, email, password})
    });

    return response.json();
}

export async function loginapi(username: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "login", username, password})
    });

    return response.json();
}

export async function profileapi(){
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "profile"})
    });

    return response.json();
}

export async function logoutapi(){
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "logout"})
    });

    return response.json();
}