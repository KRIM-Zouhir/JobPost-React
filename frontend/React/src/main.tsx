import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import HomePgae from './pages/main/home';
import SignUp from './pages/auth/signup';
import Login from './pages/auth/login';
import Profile from './pages/main/profile';
import Layout from './pages/components/layout';
import { createBrowserRouter, RouterProvider} from "react-router-dom"; 
import Jobs from './pages/main/jobs';
import Logout from './pages/auth/logout';
import Aboutus from './pages/main/aboutus';

const router = createBrowserRouter([
  {
    element: <Layout />,
    children: [
      { path: "/", element: <HomePgae />},
      { path: "/signup", element: <SignUp />},
      { path: "/login", element: <Login />},
      { path: "/profile", element: <Profile />},
      { path: "/jobs", element: <Jobs />},
      { path: "/logout", element: <Logout />},
      { path: "/aboutus", element: <Aboutus />}
    ],
  },

]);


createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <RouterProvider router={router} />
  </StrictMode>,
);
