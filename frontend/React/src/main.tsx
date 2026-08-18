import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import HomePgae from './pages/main/home';
import SignUp from './pages/auth/signup';
import Login from './pages/auth/login';
import Layout from './pages/components/layout';
import { createBrowserRouter, RouterProvider} from "react-router-dom"; 

const router = createBrowserRouter([
  {
    element: <Layout />,
    children: [
      { path: "/", element: <HomePgae />},
      { path: "/signup", element: <SignUp />},
      { path: "/login", element: <Login />}
    ],
  },

]);


createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <RouterProvider router={router} />
  </StrictMode>,
);
