"use client";

import { redirect } from "next/navigation";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { z } from "zod"
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { CategoryResponse } from "@/app/types";
import { Input } from "@/components/ui/input";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Textarea } from "@/components/ui/textarea";
import { Separator } from "@/components/ui/separator";
import { Loader2 } from "lucide-react";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Switch } from "@/components/ui/switch";

const formSchema = z.object({
    title: z.string().min(1, 'Укажите заголовок сниппета').optional(),
    body: z.string().trim().min(1, 'Нельзя опубликовать пустой сниппет').max(100_000, 'Слишком длинный сниппет'),
    category_id: z.number().gte(0, 'Выберите категорию сниппета из списка').refine(async (input) => {
        const res = await fetch('http://localhost/api/categories', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json() as Promise<CategoryResponse>;
        });
        const category = res.data.find(category => category.id === input);
        return !!category;
    }).optional(),
    burnAfterRead: z.boolean().default(false).optional(),
    expirationTime: z.coerce.date().refine((data) => data > new Date(), "Дата удаления сниппета должна отличаться от текущей.").transform(arg => arg.toISOString().split('T')[0]),
    created_at: z.coerce.date().default(() => new Date(Date.now())),
    password: z.string().min(6, 'Слишком короткий пароль').default("").optional(),
    is_public: z.boolean().default(false).optional(),
})

type InputSchema = z.input<typeof formSchema>;
type OutputSchema = z.infer<typeof formSchema>;

export default function Home() {
    const [snippetId, setSnippetId] = useState('');
    const form = useForm<InputSchema>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: 'Title',
            body: '',
            expirationTime: new Date(Date.now() + 1000 * 60 * 60 * 24),
            burnAfterRead: false
        }
    })

    async function onSubmit(values: InputSchema) {
        console.log(JSON.stringify(values));
        const res = await fetch('http://localhost/api/snippets', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(values),
        });
        const data = await res.json();
        await new Promise<void>((resolve) => {
            setTimeout(resolve, 300);
        });
        console.log(JSON.stringify(data));
        if (res.ok) {
            const snippetId = data.data.uniqueId;
            setSnippetId(snippetId);
        }
    }

    useEffect(() => {
        if (!snippetId) {
            return
        }
        redirect(`/${ snippetId }`)
    }, [snippetId]);

    return (
        <div className="flex flex-col items-center justify-center">
            <Card className="w-full max-w-md border-none sm:max-w-md md:max-w-xl lg:max-w-4xl">
                <CardHeader className={ "hidden sm:block" }>
                    <CardTitle>Создание нового сниппета</CardTitle>
                </CardHeader>
                <CardContent className="grid gap-8">
                    <div className="flex items-center space-x-4 rounded-md border p-4">
                        <Form { ...form }>
                            <form onSubmit={ form.handleSubmit(onSubmit) } className="w-full space-y-8">
                                <FormField
                                    control={ form.control }
                                    name="body"
                                    render={ ({field}) => (
                                        <FormItem>
                                            <FormControl>
                                                <Textarea
                                                    placeholder="Введите текст сниппета"
                                                    className="min-h-[200px] flex-1 p-4 md:min-h-[300px] lg:min-h-[400px]"
                                                    { ...field }
                                                />
                                            </FormControl>
                                            <FormMessage/>
                                        </FormItem>
                                    ) }
                                />
                                <Separator/>
                                <FormField
                                    control={ form.control }
                                    name="title"
                                    render={ ({field}) => (
                                        <FormItem>
                                            <FormLabel>Заголовок</FormLabel>
                                            <FormControl>
                                                <Input placeholder="Введите заголовок сниппета" { ...field } />
                                            </FormControl>
                                            <FormMessage/>
                                        </FormItem>
                                    ) }
                                />
                                <div className={ "flex items-center justify-between" }>
                                    {
                                        form.formState.isSubmitting ?
                                            <Button type="button" disabled> <Loader2
                                                className="mr-2 h-4 w-4 animate-spin"/>
                                                Публикация</Button> :
                                            <Button type="submit">Опубликовать</Button>

                                    }
                                    <Popover>
                                        <PopoverTrigger asChild>
                                            <Button variant="outline">Дополнительно</Button>
                                        </PopoverTrigger>
                                        <PopoverContent className="w-80">
                                            <FormField
                                                control={ form.control }
                                                name="burnAfterRead"
                                                render={ ({field}) => (
                                                    <FormItem
                                                        className="flex flex-row items-center justify-between rounded-lg border p-4">
                                                        <div className="space-y-0.5">
                                                            <FormLabel className="text-base">
                                                                Сжечь после прочтения
                                                            </FormLabel>
                                                        </div>
                                                        <FormControl>
                                                            <>
                                                                <Switch
                                                                    id="burnAfterRead"
                                                                    checked={ field.value }
                                                                    onCheckedChange={ field.onChange }
                                                                />
                                                            </>
                                                        </FormControl>
                                                    </FormItem>
                                                ) }
                                            />
                                        </PopoverContent>
                                    </Popover>
                                </div>
                            </form>
                        </Form>
                    </div>
                </CardContent>

            </Card>
        </div>
        // <>
        //     <div>
        //         <div>
        //             <form className="flex flex-col">
        //                 <input value={title} onChange={(e) => setTitle(e.target.value)}/>
        //                 <input value={password} onChange={(e) => setPassword(e.target.value)}/>
        //                 <textarea value={content} onChange={(e) => setContent(e.target.value)}/>
        //                 <Button
        //                     onClick={handleSubmit}
        //                     type="button">
        //                     {!!password ? 'Создать' :  'Опубликовать'}
        //                 </Button>
        //             </form>
        //         </div>
        //     </div>
        // </>
    );
}