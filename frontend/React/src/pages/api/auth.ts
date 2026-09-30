
export async function signupApi(username: string, email: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "signup", username, email, password})
    });

    return response.json();
}

export async function loginApi(username: string, password: string,) {
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "login", username, password})
    });

    return response.json();
}

export async function profileApi(){
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "profile"})
    });

    return response.json();
}

export async function logoutApi(){
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "logout"})
    });

    return response.json();
}

export async function verifySession(){
    const response = await fetch("http://localhost:8000/auth.php" , {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "verify-session"})
    });

    return response.json();
}