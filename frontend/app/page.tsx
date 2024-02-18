"use client";

import { redirect } from "next/navigation";
import { useEffect, useState } from "react";

export default function Home() {
    const [content, setContent] = useState('');
    const [snippetId, setSnippetId] = useState('');

    useEffect(() => {
        if (!snippetId) {
            return
        }
        redirect(`/${snippetId}`)
    }, [snippetId]);

    const handleSubmit = async (e: any) => {
        e.preventDefault();
        const res = await fetch('http://localhost/api/snippets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                body: content,
                expirationTime: new Date(Date.now() + 1000 * 60 * 60 * 24).toISOString().split('T')[0], // 1 day from now
            }),
        });
        const data = await res.json();
        console.log(JSON.stringify(data));
        if (res.ok) {
            const snippetId = data.data.uniqueId;
            setSnippetId(snippetId);
        }
    };

    return (
        <div className="flex flex-col items-center justify-center w-full h-screen bg-gray-100 dark:bg-gray-900">
            <div
                className="flex flex-col gap-2 items-center justify-center w-full max-w-md mx-auto mt-8 p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                <h2 className="text-xl font-semibold">Enter your snippet below</h2>
                <p className="text-sm text-gray-500 dark:text-gray-400">
                    This snippet will expire in 24 hours.
                </p>
                <form
                    className="flex flex-col gap-2 w-full max-w-md mx-auto p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                    <textarea
                        className="w-full h-32 p-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600"
                        placeholder="Enter your snippet here" name="content" id="content"
                        cols={30} rows={10}
                        required={true}
                        minLength={1}
                        autoFocus={true}
                        spellCheck={true}
                        value={content}
                        onChange={(e) => setContent(e.target.value)}
                        wrap="soft"/>
                    <button
                        className="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-gray-100 dark:border-gray-700"
                        onClick={handleSubmit}
                        id="create-snippet-button" name="create-snippet-button"
                        value="Create Snippet"
                        aria-label="Create Snippet Button"
                        role="button"
                        tabIndex={0}
                        type="button">Create Snippet
                    </button>
                </form>
            </div>
        </div>
    );
}