import React from 'react';
import { router } from '@inertiajs/react'

const projects = router.get(
    '/projects',
    {},
    { replace: false })

export default function Projects() {
    return (
      <div>
        <h1>Projects</h1>
      </div>
    );
}
