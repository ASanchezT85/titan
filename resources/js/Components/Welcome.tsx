import React from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Projects from '@/Components/Projects/Projects'

export default function Welcome() {
    return (
        <div>
            <div className="p-6 bg-white border-b border-gray-200 lg:p-8 dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent dark:border-gray-700">
                <ApplicationLogo className="block w-auto h-12" />
                <h1 className="mt-8 text-2xl font-medium text-gray-900 dark:text-white">Welcome to your Jetstream application!</h1>
                <p className="mt-6 leading-relaxed text-gray-500 dark:text-gray-400"></p>
            </div>
            <div className="grid grid-cols-1 gap-6 p-6 bg-gray-200 bg-opacity-25 dark:bg-gray-800 md:grid-cols-2 lg:gap-8 lg:p-8">
                <Projects />
            </div>
        </div>
    );
}
