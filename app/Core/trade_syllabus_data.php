<?php

function trade_syllabus_design(string $slug, ?array $trade = null): ?array
{
    if ($trade && !empty($trade['syllabus_json'])) {
        $decoded = json_decode($trade['syllabus_json'], true);
        if (is_array($decoded) && $decoded !== []) {
            return $decoded;
        }
    }

    $designs = [
        'electrician' => [
            'ncvt_code' => 'DGT-Bihar-6/2018',
            'nsqf' => 'NSQF LEVEL 4',
            'hero_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAvV9QBHE51iZ9MVn2-FMvafFj9AZLJFJM3CTebMbpyEP990nS8XKVl8DDdq0Smy5TaXpKJ_SMLp9svL9wAPwrVfZPsPy93eYowsNa01hIK4Ac2MGjDLp6r-U4ZyMNFtHxn9rcKZfVgKGXTzKhk5pLwiDKDmCEJO2cmw3qXCbyqKLc-XQCvjcNOevmcoZ0zuMy9mEXCuR9P-cGSAi7oZfy4JoUwLPxC3yPpZ4lVzOKfjbdp-7Yps8qylZmEvf3yS0Y7vbMD9UrfiSE',
            'hero_desc' => 'Master the core principles of electrical engineering through our comprehensive 2-year industrial training program designed for technical excellence.',
            'semesters' => [
                ['year' => 'YEAR 1', 'sem' => 'SEM 01', 'title' => 'Foundation & Safety', 'topics' => [
                    ['icon' => 'verified_user', 'text' => "Occupational Safety & Health"],
                    ['icon' => 'construction', 'text' => 'Common Hand Tools & Measuring'],
                    ['icon' => 'bolt', 'text' => "Basic Electricity & Ohm's Law"],
                    ['icon' => 'cable', 'text' => 'Wire Joints & Soldering'],
                ]],
                ['year' => 'YEAR 1', 'sem' => 'SEM 02', 'title' => 'Circuits & Systems', 'topics' => [
                    ['icon' => 'all_inclusive', 'text' => 'Magnetic Circuits & Electromagnetism'],
                    ['icon' => 'battery_charging_full', 'text' => 'Cells & Batteries Maintenance'],
                    ['icon' => 'waves', 'text' => 'AC Circuits & Resonance'],
                    ['icon' => 'grid_guides', 'text' => 'Polyphase Systems (3-Phase)'],
                ]],
                ['year' => 'YEAR 2', 'sem' => 'SEM 03', 'title' => 'Electrical Machines', 'topics' => [
                    ['icon' => 'settings_suggest', 'text' => 'DC Machines: Gen & Motors'],
                    ['icon' => 'electric_bolt', 'text' => 'Single & Three Phase Transformers'],
                    ['icon' => 'speed', 'text' => 'Measuring Instruments (Analog/Digital)'],
                    ['icon' => 'lightbulb', 'text' => 'Illumination & Lighting Systems'],
                ]],
                ['year' => 'YEAR 2', 'sem' => 'SEM 04', 'title' => 'Industrial Apps', 'topics' => [
                    ['icon' => 'precision_manufacturing', 'text' => 'Induction & Synchronous Motors'],
                    ['icon' => 'factory', 'text' => 'Alternators & Power Generation'],
                    ['icon' => 'home_repair_service', 'text' => 'Domestic & Industrial Wiring'],
                    ['icon' => 'developer_board', 'text' => 'Industrial Drives & Control'],
                ]],
            ],
            'career_main' => [
                'icon' => 'train',
                'title' => 'Indian Railways (ALP/Technician)',
                'desc' => "Become a part of the world's largest rail network. High demand for Electricians in maintenance, locomotive operations, and signaling.",
                'tags' => ['High Job Security', 'Central Gov Benefits'],
            ],
            'career_grid' => [
                ['icon' => 'grid_view', 'title' => 'Power Grid & Electricity Boards', 'desc' => 'Work with state and central power distribution companies as an Associate Technician or Line Inspector.', 'type' => 'card'],
                ['icon' => 'solar_power', 'title' => 'Solar Panel Installation', 'desc' => 'Growing sector with massive opportunities in green energy installations and rooftop solar projects.', 'type' => 'gold', 'bullets' => ['Entrepreneurial Opportunity', 'High-Growth Sector']],
                ['title' => 'Industrial Automation & Maintenance', 'desc' => 'Maintain complex electrical systems in manufacturing units, steel plants, and heavy engineering industries.', 'type' => 'image', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD6u5Hin5G-OsHYBT4r48a3H1wFo134iAOD0KwERPykxoWULl0x3lN5U-BN7PfcDkq3vy975iIHEoLOQaxCkKTprVXzUagrdjzd2lz5Wy1blHDs9OUSymApLekXx3LppM03fj07c68Lb0OCb2j2Z-GFw7cU-sUitkvu1hOT5kGXaDwjHbDVbQ5PdU_NEGMrMFj40wHxLUJfwm7q56nAUi218q0DjIppbEyt7gbotuoC2VWnMUJsxiu_7cIyWtMjx0kNN2eMYgNWblI'],
            ],
        ],
        'fitter' => [
            'layout' => 'industrial',
            'ncvt_code' => 'NCVT Affiliated Trade',
            'certification' => 'DGT - NCVT',
            'hero_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCz7QtQA_-KTNjDB2Vccm7pRCNNeZNGJBDDkPCwmZTTqD6hFabGBZrzeUEpTLtZn7VNpBcT4B83mTMoexYK7CNMKUIn2Vn2AbwKDOVcHmK3_sLHVcXUTQ-yztPKbNvD9yn95DhVLsTFNRE0ZeozAIEm8AxV5kBkGB5Wj0oONjygPPbdIZ_RoHan6CzjyKrGdrVceTpP0vmR58IvLqNaq_nq7xp_OMZihkQbIy7DDApOuWQb-qBs6OopAE00afI5v0Y-snF-d3s0dEA',
            'hero_desc' => 'Master the art of precision engineering. Our comprehensive 2-year program prepares you for the high-demand world of industrial assembly and maintenance.',
            'curriculum_intro' => 'The course is structured into four distinct semesters, moving from foundational manual skills to complex industrial automation and assembly techniques.',
            'semesters' => [
                [
                    'number' => '01',
                    'icon' => 'straighten',
                    'title' => 'Semester 1: Foundations',
                    'topics' => [
                        ['title' => 'Linear Measurement', 'desc' => 'Precision measuring with Vernier Calipers and Micrometers.'],
                        ['title' => 'Marking Tools', 'desc' => 'Techniques using Dividers, Punches, and Surface Plates.'],
                        ['title' => 'Bench Work: Filing & Sawing', 'desc' => 'Manual metal removal and precision cutting basics.'],
                        ['title' => 'Drilling Operations', 'desc' => 'Understanding drill bits, speeds, and machine safety.'],
                    ],
                ],
                [
                    'number' => '02',
                    'icon' => 'faucet',
                    'title' => 'Semester 2: Joining & Fabrication',
                    'topics' => [
                        ['title' => 'Sheet Metal Work', 'desc' => 'Development of surfaces and bending operations.'],
                        ['title' => 'Soldering & Brazing', 'desc' => 'Soft and hard soldering techniques for various metals.'],
                        ['title' => 'Welding (Arc & Gas)', 'desc' => 'Manual metal arc welding and oxy-acetylene processes.'],
                        ['title' => 'Riveting', 'desc' => 'Permanent fastening techniques for industrial structures.'],
                    ],
                ],
                [
                    'number' => '03',
                    'icon' => 'settings_input_component',
                    'title' => 'Semester 3: Machining',
                    'topics' => [
                        ['title' => 'Lathe Work', 'desc' => 'Turning, facing, and knurling operations on a Lathe machine.'],
                        ['title' => 'Precision Grinding', 'desc' => 'Surface finishing and sharpening of cutting tools.'],
                        ['title' => 'Heat Treatment', 'desc' => 'Hardening, tempering, and annealing of steel parts.'],
                        ['title' => 'Screw Threads', 'desc' => 'Internal and external thread cutting and standards.'],
                    ],
                ],
                [
                    'number' => '04',
                    'icon' => 'build',
                    'title' => 'Semester 4: Advanced Fitting',
                    'topics' => [
                        ['title' => 'Hydraulics & Pneumatics', 'desc' => 'Understanding fluid power and automated systems.'],
                        ['title' => 'Preventive Maintenance', 'desc' => 'Developing maintenance schedules for industrial plants.'],
                        ['title' => 'Assembly Techniques', 'desc' => 'Fitting complex components with close tolerances.'],
                        ['title' => 'Machine Installation', 'desc' => 'Foundation work and leveling of heavy machinery.'],
                    ],
                ],
            ],
            'career_intro' => 'Graduates of the Fitter Trade at Maner Private ITI are highly valued by major industrial giants across India.',
            'career_partners' => [
                ['icon' => 'directions_bus', 'name' => 'Tata Motors'],
                ['icon' => 'factory', 'name' => 'BHEL'],
                ['icon' => 'precision_manufacturing', 'name' => 'Heavy Engineering Corp'],
            ],
            'related_slug' => 'electrician',
            'related_heading' => 'Thinking of a different path?',
            'related_desc' => 'If you are more interested in electrical circuits and power systems, explore our NCVT affiliated Electrician Trade.',
            'related_label' => 'Explore Electrician Trade',
            'related_icon' => 'bolt',
            'related_bg_icon' => 'electric_bolt',
            'bscc_logo' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDitc1XzR-wvx_KGNFLXMbhYE7C2nroGapHF47QvDijanrq56OV4wRCGSwU8hV_n-csgdqVQdkWZZbw0mUau7DI0NMSD5gIZqhZllBDPMNe-2JMAbhWsL0WIaDuNSEv58ujgBlp2h70REsO4XIOrmOnXWmK7r1M3EYAPKQ6675mAZgKQt3EYoQy3wbWP5Yw0fsa6hwjFlw44FLweGI_rfhJwwORk34ESVekTummNCj69_VKPOFST1JR3jQ4RBk1CfSA5Gg9mWZklMM',
        ],
    ];

    return $designs[$slug] ?? null;
}
