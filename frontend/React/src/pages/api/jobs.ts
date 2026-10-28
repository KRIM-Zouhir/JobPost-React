
export async function getjobs(start: number, rows_per_page: number,){
    const response = await fetch("http://localhost:8000/post.php", {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action:"get", start, rows_per_page})
    })

    return response.json();
}

export async function createJob(title: string, company: string, location: string, type: string, description: string, salary: string, place: string){
    const response = await fetch("http://localhost:8000/post.php", {
        method: "POST",
        headers: { "Content-Type" : "application/json" },
        credentials: "include",
        body: JSON.stringify({action: "create", title, company, location, type, description, salary, place})
    });

    return response.json();
}