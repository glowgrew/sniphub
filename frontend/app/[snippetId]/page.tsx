"use client";

import Link from 'next/link';
import { useEffect, useState } from 'react';

export default function View({params}: { params: { snippetId: string } }) {
    const [snippet, setSnippet] = useState<any>(null);

    useEffect(() => {
        fetchSnippet(params.snippetId)
            .then((data) => {
                setSnippet(data);
            })
            .catch((error) => {
                console.error('Error fetching snippet:', error);
            });
    }, [params.snippetId]);

    const fetchSnippet = async (snippetId: string) => {
        const response = await fetch(`http://localhost/api/snippets/${snippetId}`);
        if (!response.ok) {
            throw Error;
        }
        return await response.json();
    };

    return (
        <div>
            {snippet ? (
                <div>
                    <h2>{snippet.data.title}</h2>
                    <p>{snippet.data.body}</p>
                </div>
            ) : (
                <>
                    <div>Loading...</div>
                </>
            )}
            <Link href="/">Create New Snippet</Link>
        </div>
    );
}
