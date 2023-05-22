<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class QuizController extends Controller
{
    /**
     * @OA\Info(title="My API", version="0.1")
     */
    public function index()
    {
        return response()->json(['message' => 'Hello worlds lol mathieu en roue libre']);
    }

    /**
     * @OA\Post(
     *     path="/create",
     *     @OA\Response(response="200", description="Quiz créé avec succès"),
     *     @OA\Response(response="400", description="Le nom du quiz existe déjà"),
     *     @OA\Response(response="304", description="Erreur lors de la création du quiz")
     * )
     */
    public function create(Request $request)
    {
        $quiz_name = $request->input('name');
        $existingQuiz = Quiz::where('qcmName', $quiz_name)->first();

        if ($existingQuiz) {
            return response()->json(['message' => 'Le nom du quiz existe déjà'], 400);
        } else {
            // Execute the bash script here
            $scriptOutput = exec("bash ../bin/create_quiz.sh " . escapeshellarg($quiz_name));

            // display the output of the script
            error_log($scriptOutput);

            if (str_starts_with($scriptOutput, "Quiz")) {
                // Create the quiz if the script execution was successful
                Quiz::create(['qcmName' => $quiz_name]);

                return response()->json(['message' => 'Le quiz ' . $quiz_name . ' a été créé avec succès'], 200);
            } else {
                return response()->json(['message' => 'Erreur lors de la création du quiz ' . $quiz_name], 304);
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/delete",
     *     @OA\Response(response="200", description="Quiz supprimé avec succès"),
     *     @OA\Response(response="400", description="Le nom du quiz n'existe pas")
     * )
     */
    public function delete(Request $request)
    {
        $quiz_name = $request->input('name');
        $existingQuiz = Quiz::where('qcmName', $quiz_name)->first();

        if ($existingQuiz) {
            // Ajouter la suppression du quiz ici
            $existingQuiz->delete();
            $result = shell_exec("rm -rf ../quiz_data/" . escapeshellarg($quiz_name));

            return response()->json(['message' => 'Le quiz ' . $quiz_name . ' a été supprimé avec succès'], 200);
        } else {
            return response()->json(['message' => 'Le nom du quiz n\'existe pas'], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/correct",
     *     @OA\Response(response="200", description="Quiz corrigé"),
     *     @OA\Response(response="304", description="Erreur lors de la préparation de la correction ou lors de la correction")
     * )
     */
    public function correct(Request $request)
    {
        $quiz_name = $request->input('name');
        $result = shell_exec("bash ../bin/omr.sh " . escapeshellarg($quiz_name));

        $quiz_name = $request->input('name');
        $result = shell_exec("bash ../bin/prepare_correction.sh " . escapeshellarg($quiz_name));

        if (str_starts_with($result, "All done")) {
            // Retrieve the students_answers file
            $students_answers_file = "../quiz_data/" . $quiz_name . "/" . $quiz_name . ".students_answers";

            if (file_exists($students_answers_file)) {
                // Read the file content and convert it to JSON
                $students_answers = file_get_contents($students_answers_file);
                $students_answers_json = json_encode($students_answers);

                try {
                    // Update the 'list' table with the students_answers JSON
                    DB::table('list')->where('qcmName', $quiz_name)->update(['students_answers' => $students_answers_json]);

                    // Return success response
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Erreur lors de la préparation de la correction ' . $quiz_name . '. Erreur de la base de données: ' . $e->getMessage()], 304);
                }
            } else {
                return response()->json(['message' => 'Erreur lors de la préparation de la correction ' . $quiz_name . '. Fichier students_answers non trouvé.'], 304);
            }
        } else {
            return response()->json(['message' => 'Erreur lors de la préparation de la correction ' . $quiz_name], 304);
        }

        $result = shell_exec("bash ../bin/correct_quiz.sh " . escapeshellarg($quiz_name));

        if (str_starts_with($result, "All done")) {
            return response()->json(['message' => 'Le quiz ' . $quiz_name . ' a été corrigé '], 200);
        } else {
            return response()->json(['message' => 'Erreur lors de la correction ' . $quiz_name], 304);
        }
    }

    /**
     * @OA\Post(
     *     path="/omr_errors_resolved",
     *     @OA\Response(response="200", description="Execution du script OMR errors réussie sur le quizz"),
     *     @OA\Response(response="304", description="Erreur lors du script OMR errors")
     * )
     */
    public function omr_errors_resolved(Request $request)
    {
        $quiz_name = $request->input('name');
        $result = shell_exec("bash ../bin/omr_errors_resolved.sh " . escapeshellarg($quiz_name));

        if (str_starts_with($result, "All done")) {
            return response()->json(['message' => 'Execution du script OMR errors réussie sur le quizz ' . $quiz_name], 200);
        } else {
            return response()->json(['message' => 'Erreur lors du script OMR errors' . $quiz_name], 304);
        }
    }

    /**
     * @OA\Post(
     *     path="/upload_copies",
     *     @OA\Response(response="200", description="Fichier de correction uploadé avec succès"),
     *     @OA\Response(response="500", description="Erreur lors de l'upload du fichier de correction")
     * )
     */
    public function upload_copies(Request $request)
    {
        $quiz_name = $request->query('name');

        // validate request
        $request->validate([
            'file' => 'required|file',
        ]);

        // Get the uploaded file from the request
        $file = $request->file('file');

        // Define the path where the file will be stored
        $path = 'quiz_data/' . $quiz_name . '/omr_input';

        // filename is number of copies in the folder + 1
        $filename = count(Storage::disk('quiz_data')->files($path)) + 1;

        // Store the file in the defined path with name qcm.marking but not the first line of the file
        $result = Storage::disk('quiz_data')->putFileAs($path, $file, $filename . '.jpg');

        if ($result) {
            // Return a success response
            return response()->json(['message' => 'Fichier de correction uploadé avec succès'], 200);
        } else {
            // If the file upload failed, return an error response
            return response()->json(['message' => 'Erreur lors de l\'upload du fichier de correction'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/upload_correction",
     *     @OA\Response(response="200", description="Fichier de correction uploadé avec succès"),
     *     @OA\Response(response="500", description="Erreur lors de l'upload du fichier de correction")
     * )
     */
    public function upload_correction(Request $request)
    {
        $quiz_name = $request->input('name');

        // validate request
        $request->validate([
            'file' => 'required|file',
        ]);

        // Get the uploaded file from the request
        $file = $request->file('file');

        // Define the path where the file will be stored
        $path = 'quiz_data/' . $quiz_name;

        // Store the file in the defined path with name qcm.marking but not the first line of the file
        $result = Storage::disk('quiz_data')->putFileAs($path, $file, 'qcm.marking');

        if ($result) {
            // If the file was successfully uploaded

            // Define the full path of the file
            $filePath = $path . '/qcm.marking';

            // Get the file content as an array
            $fileContent = Storage::disk('quiz_data')->get($filePath);

            // Convert the file content into an array of lines
            $fileContentLines = explode(PHP_EOL, $fileContent);

            // Remove the first line
            array_shift($fileContentLines);

            // Convert the remaining lines back into a string
            $updatedFileContent = implode(PHP_EOL, $fileContentLines);

            // Save the file content without the first line
            Storage::disk('quiz_data')->put($filePath, $updatedFileContent);

            // Return a success response
            return response()->json(['message' => 'Fichier de correction uploadé avec succès'], 200);
        } else {
            // If the file upload failed, return an error response
            return response()->json(['message' => 'Erreur lors de l\'upload du fichier de correction'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/upload_list",
     *     @OA\Response(response="200", description="PDF généré"),
     *     @OA\Response(response="500", description="Erreur lors de l'upload du fichier de correction")
     * )
     */
    function upload_list(Request $request)
    {
        $quiz_name = $request->input('name');

        // validate request
        $request->validate([
            'file' => 'required|file',
        ]);

        // Get the uploaded file from the request
        $file = $request->file('file');

        // Store the file in the defined path with name qcm.marking but not the first line of the file
        $result = Storage::disk('quiz_data')->putFileAs('bin', $file, 'liste_id.csv');

        if ($result) {
            // // 2. Run the python script
            $result = shell_exec("python3 ../bin/autocompletion.py");

            // // 3. Move the output file to the public directory
            $output_path = $quiz_name . '.pdf';

            $result = rename('../bin/sortie.pdf', '../public/' . $output_path);
            error_log($result);

            // return path with complete url
            return response()->json(['success' => 'PDF Generated.', 'path' => $_ENV['APP_URL'] . '/' . $output_path], 200);
        } else {
            // If the file upload failed, return an error response
            return response()->json(['message' => 'Erreur lors de l\'upload du fichier de correction'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/check_correction",
     *     @OA\Response(response="200", description="Liste des quiz")
     * )
     */
    public function check_correction()
    {
        $quiz_list = DB::table('list')->get();

        return response()->json(['quiz_list' => $quiz_list], 200);
    }
}