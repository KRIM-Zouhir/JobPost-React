import { useState } from "react";
import { login } from '../api/auth.ts'
import { useNavigate } from 'react-router-dom';


function Login (){
    const [message, setMessage] = useState("");
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");

    const redirect = useNavigate();

    async function handleLogin(e: React.FormEvent){
        e.preventDefault();
        const fetch = await login(username, password);
        if(fetch.success){
            redirect("/profile");
        }else{
            setMessage(fetch.message);
        }
    };

    return(
            <div className="min-h-screen bg-black flex flex-col items-center justify-center">   
                <p className="text-white">{message}</p>
                    <form  onSubmit={handleLogin}
                            className="flex w-full max-w-sm flex-col items-center justify-center gap-2 text-white">
                        <input value={username} onChange={(e) => setUsername(e.target.value)} placeholder="Username/Email" ></input>
                        <input value={password} onChange={(e) => setPassword(e.target.value)} placeholder="Password" ></input>

                        <button type="submit">Create</button>
                    </form>
            </div>
    )
};

export default Login;
