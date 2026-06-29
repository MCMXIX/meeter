<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 220">
        <!-- Logo Icon -->
        <g transform="translate(90,25)">

            <!-- Heart -->
            <path class="heart"
            d="
            M60 130
            C20 100 0 75 0 45
            C0 18 20 0 45 0
            C60 0 73 8 82 21
            C91 8 104 0 119 0
            C144 0 164 18 164 45
            C164 75 144 100 104 130
            L82 148
            Z"/>

            <!-- Gauge -->
            <path class="gauge" d="M32 60 A50 50 0 0 1 132 60"/>

            <!-- Gauge ticks -->
            <line class="tick" x1="43" y1="58" x2="38" y2="50"/>
            <line class="tick" x1="61" y1="43" x2="58" y2="34"/>
            <line class="tick" x1="82" y1="38" x2="82" y2="28"/>
            <line class="tick" x1="103" y1="43" x2="106" y2="34"/>
            <line class="tick" x1="121" y1="58" x2="126" y2="50"/>

            <!-- Needle -->
            <line class="needle" x1="82" y1="60" x2="112" y2="38"/>

            <!-- Needle pivot -->
            <circle cx="82" cy="60" r="6" fill="#2D2D44"/>

        </g>

        <!-- Text -->
        <text x="285" y="120" class="text">meeter</text>
    </svg>
</template>
<style scoped>
    .text {
        font-family: "Poppins", "Nunito", Arial, sans-serif;
        font-size: 64px;
        font-weight: 700;
        fill: #2D2D44;
    }

    .heart {
        fill: #FF4D6D;
    }

    .gauge {
        fill: none;
        stroke: #2D2D44;
        stroke-width: 8;
        stroke-linecap: round;
    }

    .tick {
        stroke: #2D2D44;
        stroke-width: 5;
        stroke-linecap: round;
    }

    .needle {
        stroke: #2D2D44;
        stroke-width: 7;
        stroke-linecap: round;
    }
</style>
