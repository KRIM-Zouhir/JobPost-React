import { useState, useEffect } from 'react';
import { profile } from '../api/auth.ts'
import { Link, useNavigate } from 'react-router-dom';

function Profile(){
    const [username, setUsername] = useState("");
    const [email, setEmail] = useState("");
    const [role, setRole] = useState("");
    
    const redirect = useNavigate();

    useEffect(() => {
        async function loadProfile() {
            const result = await profile();

            if(result.success){
                setUsername(result.user.username);
                setEmail(result.user.email);
                setRole(result.user.role);
            }else{
                redirect("/");
            }
        }

        loadProfile();
    } , [redirect])

    return(
        <div className="min-h-screen bg-black ">
            <div className='text-white text-center'>
                <h3 className="">Profile</h3>

                <p className=''>{username}</p>
                <p className=''>{email}</p>
                <p className=''>{role}</p>
                
                <button className='border-white'><Link to="/logout">Logout</Link></button>
            </div>


        </div>
    )

}

export default Profile;