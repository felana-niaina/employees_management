<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Auth;

class EmployeeController extends Controller
{
    // Lister les employés
    public function index()
    {
        $employees = Employee::all();
        
        \Log::info('Liste des employés', ['employees' => $employees]);

        // Retourner les données dans une réponse JSON
        return response()->json($employees);
    }

    // Ajouter un employé
    public function store(Request $request)
    {
       // Validation avec le nom correct des champs
        $validated = $request->validate([
            'fullName' => 'required|unique:employees,fullName',
            'date_of_birth' => 'required|date', // Correspond au champ envoyé depuis le frontend
        ]);

        // Sauvegarder en renvoyant les noms correspondant aux colonnes de la BDD
        $employee = Employee::create([
            'fullName' => $validated['fullName'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        return response()->json(['message' => 'Employé ajouté', 'employee' => $employee]);
    }

    // Mettre à jour un employé
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'fullName' => 'required|unique:employees,fullName,' . $employee->idEmployee,
            'date_of_birth' => 'required|date',
        ]);

        $employee->update($validated);

        return response()->json(['message' => 'Employé mis à jour', 'employee' => $employee]);
    }

    // Supprimer un employé
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(['message' => 'Employé supprimé']);
    }

}
