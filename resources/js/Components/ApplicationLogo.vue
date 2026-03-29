<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none">
        <defs>
            <linearGradient id="ticketGlow" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#3b82f6" />
                <stop offset="100%" stop-color="#8b5cf6" />
            </linearGradient>
        </defs>
        <!-- Main Ticket Body -->
        <path d="M4 16C4 13.7909 5.79086 12 8 12H40C42.2091 12 44 13.7909 44 16V19C41.7909 19 40 20.7909 40 23C40 25.2091 41.7909 27 44 27V32C44 34.2091 42.2091 36 40 36H8C5.79086 36 4 34.2091 4 32V27C6.20914 27 8 25.2091 8 23C8 20.7909 6.20914 19 4 19V16Z" fill="url(#ticketGlow)"/>
        
        <!-- Text Lines -->
        <path d="M16 20H28" stroke="white" stroke-width="3" stroke-linecap="round"/>
        <path d="M16 26H24" stroke="white" stroke-width="3" stroke-linecap="round"/>
        
        <!-- Left Stub Circle -->
        <circle cx="12" cy="23" r="2" fill="white"/>
        
        <!-- Tear-off Dashed Line -->
        <line x1="34" y1="12" x2="34" y2="36" stroke="white" stroke-width="2" stroke-dasharray="3 3" opacity="0.6"/>
    </svg>
</template>
