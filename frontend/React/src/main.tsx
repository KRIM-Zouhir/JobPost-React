import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import SignUp from './pages/auth/signup.tsx';
import Layout from './pages/components/layout';
import HomePgae from './pages/main/home.tsx';
import { createBrowserRouter, RouterProvider} from "react-router-dom"; 

const router = createBrowserRouter([
  {
    element: <Layout />,
    children: [
      { path: "/", element: <HomePgae />},
      { path: "/signup", element: <SignUp />}
    ],
  },

]);


createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <RouterProvider router={router} />
  </StrictMode>,
);
