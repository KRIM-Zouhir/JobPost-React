import { useState, useEffect } from 'react';
import { logoutApi } from '../api/auth';
import { useNavigate } from 'react-router-dom';


function Logout(){
    const [message, setMessage] = useState();

    const redirect = useNavigate();
    useEffect(() => {
        async function handleLogout() {
            const response = await logoutApi() ; 
            
            if(response.success){
                setMessage(response.message)
                redirect("/", {replace: true})
            }
        }
    
        handleLogout();
    }, [redirect])
    return(
        <div>

        </div>
    )
}

export default Logout;