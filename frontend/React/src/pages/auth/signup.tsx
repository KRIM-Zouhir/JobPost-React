import { useState, useEffect } from "react";
import { signupApi, verifySession } from '../api/auth.ts'
import { useNavigate } from 'react-router-dom';

function SignUp (){
    const [message, setMessage] = useState("");
    const [username, setUsername] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmpassword, setConfirmPassword] = useState("");

    async function handleSignup(e: React.FormEvent){
        e.preventDefault();
        const redirect = useNavigate();

        useEffect(() =>{
            async function checkSession(){
                const session = await verifySession();
                if(session.success){
                    redirect("/profile")
                }
            }

            checkSession();

        }, [redirect])

        const fetch = await signupApi(username, email, password);
        console.log(fetch);
        setMessage(fetch.message);
    };

    return(
            <div className="min-h-screen bg-black flex flex-col items-center justify-center">   
                <p className="text-white">{message}</p>
                    <form   onSubmit={handleSignup}
                            className="flex w-full max-w-sm flex-col items-center justify-center gap-2 text-white">
                        <input value={username} onChange={(e) => setUsername(e.target.value)} placeholder="Username" ></input>
                        <input value={email} onChange={(e) => setEmail(e.target.value)} placeholder="Email" ></input>
                        <input value={password} onChange={(e) => setPassword(e.target.value)} placeholder="Password" ></input>
                        <input value={confirmpassword} onChange={(e) => setConfirmPassword(e.target.value)} placeholder="Confirm Password" ></input>

                        <button type="submit">Create</button>
                    </form>
            </div>
    )
};

export default SignUp;
