import { useState, useEffect } from 'react';
import { profileApi, verifySession} from '../api/auth.ts'
import { Link, useNavigate } from 'react-router-dom';

function Profile(){
    const [username, setUsername] = useState("");
    const [email, setEmail] = useState("");
    const [role, setRole] = useState("");
    
    const redirect = useNavigate();

    useEffect(() => {
        async function checkSession(){
            const session = await verifySession();
            if(!session.success){
                redirect("/login")
            }
        }

        checkSession();
        async function loadProfile() {
            const result = await profileApi();

            if(result.success){
                setUsername(result.user.username);
                setEmail(result.user.email);
                setRole(result.user.role);
            }
        }

        loadProfile();
    } , [])

    return(
        <div className='bg-black text-white'>
            <div className="min-h-screen flex flex-col justify-center items-center gap-4">
                <div className='text-center'>
                    <h3 className="p-5">Profile</h3> 

                    <div className=''>
                        <p className=''>{username}</p>
                        <p className=''>{email}</p>
                        <p className=''>{role}</p>
                    </div>
                    
                    <button className='border-white'><Link to="/logout">Logout</Link></button>
                </div>
            </div>
        </div>
    )

}

export default Profile;