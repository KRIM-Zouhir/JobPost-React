import { useState, useEffect } from 'react';
import { getjobs } from '../api/applications.ts'

type Job = {
    postid: string;
    admin_id: string;
    title: string;
    company: string;
    location: string;
    type: string;
    description: string;
    salary: string;
    place: string;
    is_active: string;
}


function Jobs(){
    const start = 0;
    const rows_per_page = 10;
    const [jobs, setJobs] = useState<Job[]>([]);

    useEffect(() => {
        async function handleLoadJobs() {
            const response = await getjobs(start, rows_per_page); 
    
            if(response.success){
                setJobs(response.data)
            }
        }
    
        handleLoadJobs();
    }, [])


    return(
        <div>
            <h3>Jobs</h3>
            {jobs.map((job) => 
                <article key={job.postid}>
                    <h5>{job.title}</h5>
                    <h5>{job.company}</h5>
                    <p>{job.location} | {job.place}</p>
                    <p>{job.type}</p>
                    <p>{job.description}</p>
                    <p>{job.salary}</p>
                </article>
            )}
        </div>
    )
}

export default Jobs;