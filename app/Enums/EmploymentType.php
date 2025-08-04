<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FullTime = 'Vollzeit';
    case PartTime = 'Teilzeit';
    case Contract = 'Vertrag';
    case Internship = 'Praktikum';
    case Temporary = 'Befristet';
    case Volunteer = 'Freiwillig';
    case Freelance = 'Freelance';
}
