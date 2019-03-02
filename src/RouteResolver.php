<?php


namespace SimpelDigitaal\RoutingModels;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ViewFinderInterface;
use RoutingModels\Exceptions\RoutingModelsException;

class RouteResolver extends Controller
{

    /**
     * Entry point for the route of a RoutingRecord. Resolves to a view.
     *
     * @param RoutingRecord $record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws RoutingModelsException
     */
    public function resolve(RoutingRecord $record)
    {
        $subject = $record->subject;

        $viewPath = $this->resolveViewName($subject);

        view()->exists($viewPath) ?: $this->generateView($viewPath);

        return view($viewPath, compact('subject', 'record'));
    }

    /**
     * @param Model $subject
     * @return string
     */
    protected function resolveViewName(Model $subject)
    {
        $uniqueNameForModel = $subject->getTable();

        $namespace = RoutingModelsServiceProvider::VIEW_NAME_SPACE;

        $delimiter = ViewFinderInterface::HINT_PATH_DELIMITER;

        return "{$namespace}{$delimiter}{$uniqueNameForModel}.show";
    }

    /**
     * Will generate a non-existing view for the model.
     *
     * @param string $viewName
     * @return bool
     * @throws RoutingModelsException
     */
    protected function generateView(string $viewName)
    {
        $template = __DIR__.'/views/template.blade.php';
        $applicationsViewPath = $this->resolveApplicationsViewPath();

        $segments = explode(ViewFinderInterface::HINT_PATH_DELIMITER, $viewName);

        $destinationFileName = str_replace('.', '/', $segments[1]).".blade.php";

        $destination =  str_finish($applicationsViewPath, '/').$destinationFileName;


        if (!file_exists($destination)) {
            $this->makeDirectory(dirname($destination));

            return copy($template, $destination);
        }
        return true;
    }

    /**
     * @return string
     * @throws RoutingModelsException
     */
    protected function resolveApplicationsViewPath()
    {
        $namespace = RoutingModelsServiceProvider::VIEW_NAME_SPACE;

        if (is_array(config('view.paths'))) {
            $applicationViewPaths = config('view.paths');
            foreach ($applicationViewPaths as $viewPath) {
                if (is_dir($appPath = $viewPath.'/vendor/'.$namespace)) {
                    return $appPath;
                }
            }
            return $this->makeApplicationsViewPath($applicationViewPaths[0]);
        }

        throw new RoutingModelsException('Application has no viewPath configured');
    }


    /**
     * @param $path
     * @return string
     */
    protected function makeApplicationsViewPath($path)
    {
        $namespace = RoutingModelsServiceProvider::VIEW_NAME_SPACE;
        $viewPath = $path.'/vendor/'.$namespace;

        $this->makeDirectory($viewPath);

        view()->addNamespace($namespace, $viewPath);
        return $viewPath;
    }


    /**
     * @param $directory
     */
    protected function makeDirectory($directory)
    {
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}