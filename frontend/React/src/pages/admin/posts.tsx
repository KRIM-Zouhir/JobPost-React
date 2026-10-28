import { useState } from "react";
import { createJob } from "../api/jobs.ts";

function Posts(){
    const [message, setMessage] = useState("");

    const [title, setTitle] = useState("");
    const [company, setCompany] = useState("");
    const [location, setLocation] = useState("");
    const [type, setType] = useState("");
    const [description, setDescription] = useState("");
    const [salary, setSalary] = useState("");
    const [place, setPlace] = useState("");
    const [showForm, setShowForm] = useState(false);

    async function createPost(title: string, company: string, location: string, type: string, description: string, salary: string, place: string){
        const result = await createJob(title, company, location, type, description, salary, place);
        if(result.success){
            setMessage(result.message);
        }
    }

    return(
        <div className='bg-black text-white'>
            <div className="min-h-screen flex flex-col justify-center items-center gap-4">
                <div className='text-center'></div>
                    <p>Posts</p>

                    <button type="button" onClick={() => (setShowForm(prev => !prev))}>
                        Add post
                    </button>

                    {showForm && (
                        <div className="">
                            <input type="text" value={title} placeholder="Title" onChange={(e) => setTitle(e.target.value)} />
                            <input type="text" value={company} placeholder="Company" onChange={(e) => setCompany(e.target.value)} />
                            <input type="text" value={location} placeholder="Location" onChange={(e) => setLocation(e.target.value)}/>
                            <input type="text" value={type} placeholder="Type" onChange={(e) => setType(e.target.value)}/>
                            <input type="text" value={description} placeholder="Description" onChange={(e) => setDescription(e.target.value)}/>
                            <input type="text" value={salary} placeholder="Salary" onChange={(e) => setSalary(e.target.value)}/>
                            <input type="text" value={place} placeholder="Place" onChange={(e) => setPlace(e.target.value)}/>
                            <button type="button" onClick={() => createPost(title, company, location, type, description, salary, place)}>Create</button>
                        </div>
                    )}
            </div>
        </div>
    )
}

export default Posts;
