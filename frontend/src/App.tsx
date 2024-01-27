import {useState} from 'react'

function App() {
    const [count, setCount] = useState(0)

    return (
        <>
            <h1>SnipHub</h1>
            <button onClick={() => setCount((count) => count + 1)}>
                Count is {count}
            </button>
        </>
    )
}

export default App