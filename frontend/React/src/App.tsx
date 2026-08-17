import { useState } from "react";


function App (){
  const [count, setCount] = useState(0);
  return (
    <div>
      <p>the number of clicks : {count}</p>
      <button onClick={() => setCount( count + 1 )}>Click me</button>
    </div>
  )
}

export default App; 