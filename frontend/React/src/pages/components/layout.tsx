import { Outlet } from "react-router-dom";
import NavBar from "./header";

function Layout() {
  return (
    <>
      <NavBar />
      <Outlet />
    </>
  );
}

export default Layout;