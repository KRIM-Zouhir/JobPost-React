import { useState, useEffect } from 'react';
import { getjobs } from '../api/applications.ts'
import {verifySession} from '../api/auth.ts'
import { applyAPI, verifyApp } from '../api/jobs.ts'
import { useNavigate } from 'react-router-dom';

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
    const rows_per_page = 9;
    const [jobs, setJobs] = useState<Job[]>([]);
    const [appliedPostIds, setAppliedPostIds] = useState<Set<string>>(new Set());
    const [selectedJob, setSelectedJob] = useState<{postid: string; posttitle: string} | null>(null);

    const [message, setMessage] = useState("");
    const [sessionAuth, setSessionAuth] = useState(false);
    const [cvfile, setCv] = useState<File | null>(null);
    const [coverfile, setCover] = useState<File | null>(null);

    const redirect = useNavigate();


    useEffect(() => {

        async function checkSession(){
            const session = await verifySession();
            if(session.success){
                setSessionAuth(true);
            }
        }

        checkSession();
        async function handleLoadJobs() {
            const response = await getjobs(start, rows_per_page); 
    
            if(response.success){
                setJobs(response.data);
            }
        }
    
        handleLoadJobs();
    }, [redirect])

    useEffect(() => {
        async function handlePostIds() {
            const response = await verifyApp();
            if(response.success){
                const ids = new Set<string>(response.posts_id.map((id: any) => String(id)));
                setAppliedPostIds(ids);
            }
        }

        handlePostIds();
    }, [])

    function handleCvChange(e: React.ChangeEvent<HTMLInputElement>){
        if(e.target.files && e.target.files[0]){
            setCv(e.target.files[0]);
        }
    }
    function handleCoverChange(e: React.ChangeEvent<HTMLInputElement>){
        if(e.target.files && e.target.files[0]){
            setCover(e.target.files[0]);
        }
    }


    async function applyJob(e : React.FormEvent, postid: string, posttitle: string){
        e.preventDefault();
        if(!cvfile){
            setMessage("No CV file found !")
            return;
        } 

        const applyResult = await applyAPI(postid, posttitle, cvfile, coverfile)
        setMessage(applyResult.message)

    }

    


    return(
        <div className='bg-black text-white'>
            <div className='min-h-screen flex flex-col items-center justify-center'>
                <h3 className='text-center pb-5'>Jobs</h3>
                    <p className="text-green-700">{message}</p>
                    
                <div className='grid grid-cols-3 gap-4 p-4'>
                    {jobs.map((job) => 

                    <article key={job.postid} >
                        <h5>{job.title}</h5>
                        <h5>{job.company}</h5>
                        <p>{job.location} | {job.place}</p>
                        <p>{job.type}</p>
                        <p>{job.description}</p>
                        <p>{job.salary}</p>
                        <button type="button" onClick={() => setSelectedJob({postid: job.postid, posttitle: job.title})}>
                            {appliedPostIds.has(job.postid) ? "Applied" : "Apply"}
                        </button>
                    </article>


                    )}

                </div>

                {selectedJob && sessionAuth ? 
                ( 
                    <div className='pt-5' id="applyForm">
                        <p>{selectedJob.posttitle}</p>
                        <form onSubmit={(e) => applyJob(e, selectedJob.postid, selectedJob.posttitle)}>
                            <input type="file" accept="file/*" onChange={handleCvChange}/>
                            <input type="file" accept="file/*" onChange={handleCoverChange}/>
                            <button type="submit">Submit</button>
                        </form>
                    </div>
                ) : selectedJob && !sessionAuth ?
                (
                    <div>
                        <p>Login to apply</p>
                    </div>
                ) : null
                }
            </div>
        </div>
    )
}

export default Jobs;