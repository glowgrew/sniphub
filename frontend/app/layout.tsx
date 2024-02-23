import type { Metadata } from "next";
import { Inter } from "next/font/google";
import Image from "next/image";
import "./globals.css";
import { ThemeProvider } from "@/components/theme-provider"
import { cn } from "@/lib/utils";
import { ModeToggle } from "@/app/theme";

const inter = Inter({subsets: ["latin"]});

export const metadata: Metadata = {
    title: "Create Next App",
    themeColor: [
        {media: "(prefers-color-scheme: light)", color: "white"},
        {media: "(prefers-color-scheme: dark)", color: "black"},
    ],
    description: "Generated by create next app",
};

export function Navbar() {
    return (
        <>
            <nav className="flex justify-between items-center w-full p-2 md:p-4 lg:p-8">
                <div className={ "hidden sm:flex" }>
                    <ModeToggle/>
                </div>
                <div className="flex justify-center flex-grow">
                    <div className="logo-container w-auto h-auto">
                        <a className="block dark:hidden" href="/">
                            <Image src="/logo_dark.svg" alt="SnipHub Logo" width={ 256 } height={ 32 }/>
                        </a>
                        <a className="hidden dark:block" href="/">
                            <Image src="/logo_light.svg" alt="SnipHub Logo" width={ 256 } height={ 32 }/>
                        </a>
                    </div>
                </div>
            </nav>
        </>
    )
}

export default function RootLayout({
                                       children,
                                   }: Readonly<{
    children: React.ReactNode;
}>) {
    return (
        <>
            <html lang="en" suppressHydrationWarning>
            <head/>
            <body
                className={ cn(
                    "min-h-screen bg-background font-sans antialiased",
                    inter.className)
                }
            >
            <ThemeProvider attribute="class" defaultTheme="system" enableSystem>
                <Navbar/>
                <div className="relative min-h-screen">
                    <div className="flex-1">{ children }</div>
                </div>
            </ThemeProvider>
            </body>
            </html>
        </>
    );
}
