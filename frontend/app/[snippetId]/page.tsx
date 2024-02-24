"use client";

import React, { useEffect, useState } from 'react';
import { ClipboardCopy, Clock, Eraser, Eye, Loader2, Pencil, ScrollText } from "lucide-react";
import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { Separator } from "@/components/ui/separator";
import { Badge } from "@/components/ui/badge";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";


export default function View({params}: { params: { snippetId: string } }) {
    const [snippet, setSnippet] = useState<any>(null);

    useEffect(() => {
        fetchSnippet(params.snippetId)
            .then((data) => {
                console.log(JSON.stringify(data));
                setSnippet(data);
            })
            .catch((error) => {
                console.error('Error fetching snippet:', error);
            });
    }, [params.snippetId]);

    const fetchSnippet = async (snippetId: string) => {
        const response = await fetch(`http://localhost/api/snippets/${ snippetId }`);
        if (!response.ok) {
            throw Error;
        }
        return await response.json();
    };

    const rtf = new Intl.RelativeTimeFormat('ru', {numeric: 'auto'});
    const getRelativeTime = (date: Date, ago: boolean = true) => {
        const now = new Date();
        const diff = now.getTime() - date.getTime();
        const diffDays = Math.floor(diff / (1000 * 60 * 60 * 24));
        const diffHours = Math.floor(diff / (1000 * 60 * 60)) % 24;
        const diffMinutes = Math.floor(diff / (1000 * 60)) % 60;
        const diffSeconds = Math.floor(diff / 1000) % 60;

        if (diffDays > 0) {
            return rtf.format(ago ? -diffDays : diffDays, 'day');
        } else if (diffHours > 0) {
            return rtf.format(ago ? -diffHours : diffHours, 'hour');
        } else if (diffMinutes > 0) {
            return rtf.format(ago ? -diffMinutes : diffMinutes, 'minute');
        } else {
            return rtf.format(ago ? -diffSeconds : diffSeconds, 'second');
        }
    }
    const dtf = new Intl.DateTimeFormat('ru', {
        dateStyle: 'full',
        timeStyle: 'long',
        timeZone: 'Europe/Moscow',
    });
    const getDateTime = (date: Date) => {
        return dtf.format(date);
    }

    return (
        <div><TooltipProvider>
            { !snippet && (
                <div className="flex h-full w-full items-center justify-center">
                    <Loader2 className="h-8 w-8 animate-spin"/>
                </div>
            ) }
            { snippet && (
                <div>
                    <div className="flex h-full flex-col items-center justify-center gap-4 p-4">
                        <div className="flex items-center p-2">
                            <div className="flex items-center gap-2">
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <Button variant="outline" disabled={ !snippet }>
                                            <ClipboardCopy className="mr-2 h-4 w-4"/>Копировать
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Копировать в буфер обмена</TooltipContent>
                                </Tooltip>
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <Button variant="outline" disabled={ !snippet }>
                                            <Pencil className="mr-2 h-4 w-4"/>Изменить
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Создать копию и изменить</TooltipContent>
                                </Tooltip>
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <Button variant="outline" disabled={ !snippet }>
                                            <ScrollText className="mr-2 h-4 w-4"/>Только текст
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Открыть текст сниппета</TooltipContent>
                                </Tooltip>
                                { !!snippet.data.burnAfterRead && <Badge variant="destructive">Самоуничтожение</Badge> }
                                { !!snippet.data.views &&
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Badge variant="outline">
                                                <Eye className="mr-2 h-4 w-4"/> { snippet.data.views }
                                            </Badge>
                                        </TooltipTrigger>
                                        <TooltipContent>Просмотры</TooltipContent>
                                    </Tooltip>
                                }
                                { !!snippet.data.createdAt &&
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Badge>
                                                <Clock
                                                    className="mr-2 h-4 w-4"/> { getRelativeTime(new Date(snippet.data.createdAt)) }
                                            </Badge>
                                        </TooltipTrigger>
                                        <TooltipContent>Был
                                            создан { getDateTime(new Date(snippet.data.createdAt)) }</TooltipContent>
                                    </Tooltip>
                                }
                                { !!snippet.data.expirationTime &&
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Badge variant="outline">
                                                <Eraser
                                                    className="mr-2 h-4 w-4"/> { getRelativeTime(new Date(snippet.data.expirationTime), false) }
                                            </Badge>
                                        </TooltipTrigger>
                                        <TooltipContent>Сотрется
                                            через { getDateTime(new Date(snippet.data.expirationTime)) }</TooltipContent>
                                    </Tooltip>
                                }
                            </div>
                        </div>
                    </div>
                    <div
                        className="flex h-full flex-col items-center justify-center gap-4 p-4">
                        <Card className="w-full max-w-md border-none sm:max-w-md md:max-w-xl lg:max-w-4xl">
                            <CardHeader className={ "hidden sm:block" }>
                                <CardTitle>
                                    { snippet.data.title }
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="grid gap-8">
                                { snippet.data.body }
                            </CardContent>
                        </Card>
                    </div>
                </div>
            ) }
        </TooltipProvider></div>
    );
}
