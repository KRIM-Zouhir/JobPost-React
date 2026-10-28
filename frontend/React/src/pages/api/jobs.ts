export async function getjobs(start: number, rows_per_page: number,){
    const response = await fetch("http://localhost:8000/post.php", {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({start, rows_per_page})
    })

    return response.json();
}