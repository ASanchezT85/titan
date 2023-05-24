import React, { PropsWithChildren } from 'react';

type Props = {
    name:string,
    description: string,
    status: string
}

export default function Project({project}: PropsWithChildren<{ project: Props }>) {
    return (
        <div>
            <div className="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" className="w-6 h-6 stroke-gray-400">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <h2 className="ml-3 text-xl font-semibold text-gray-900 dark:text-white">{project.name}</h2>
            </div>
            <p className="mt-4 text-sm leading-relaxed text-gray-500 dark:text-gray-400">{project.description}</p>
            <p className="mt-4 text-sm">
                <a href="https://laravel.com/docs" className="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                    Explore the documentation
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" className="w-5 h-5 ml-1 fill-indigo-500 dark:fill-indigo-200">
                        <path fillRule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clipRule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
    );
  }
