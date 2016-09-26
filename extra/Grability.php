<?php 

/**
 * Punto número 2 - Rectorizar ejemplo
 */

/**
 * Policy
 */
class ServicePolicy
{
    use HandlesAuthorization;

    public function confirm(User $user, Service $service)
    {
    	return ($service->status_id == 1 && ! is_null($service->driver_id));
    }
}


/**
 * Request
 */
class ConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return $this->user()->can('confirm', $this->route('services'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'services' => 'required|exists:services',
            'drivers'  => 'required|exists:drivers'	
        ];
    }
}

/**
 * Entities
 */
class Service extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status_id', 'driver_id'];
}

class Driver extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['available'];
}

class User extends Model
{
	public function isIosType()
	{
		return $this->type == '1';
	}

	public function isAndroidType()
	{
		return $this->type == '2';	
	}
}


/**
 * Repositories
 */
class BaseRepository
{
	protected function update(Model $model, array $data) 
	{
		$model->fill($data);
		return $model->save();
	}
}

class ServiceRepository extends BaseRepository
{
	public function confirm(Service $service, Driver $driver) 
	{
		return $this->update($service, [
			'driver_id' => $driver->id,
			'status'	=> 2,
			'car_id'	=> $driver->car_id
		]);
	}	

}

class DriverRepository extends BaseRepository
{
	public function disable(Driver $driver)
	{
		return $this->update($driver, [
			'available' => 0
		]);
	}	
}


/**
 * Services
 */
class ServiceService 
{
	public function confirmService(Service $service, Driver $driver) 
	{
		return $this->serviceRepository->update($service, $driver);
	}	
}

class PushService 
{
	protected $push;

	public function __construct(Push $push)
    {
        $this->push = $push;
    }

	protected function pushMessageIos(User $user, $message, array $data) 
	{
		return $this->push->ios($user->uuid, $message, 1, 'honk.wav', 'Open', $data);
	}

	protected function pushMessageAndroid(User $user, $message, array $data) 
	{
		return $this->push->android2($user->uuid, $message, 1, 'default', 'Open', $data);
	}	

	public function pushUserMessage(User $user, $message, array $data)
	{
		if($user->isIosType()) {
			return $this->pushMessageIos($user, $message, $data);
		}
		else if($user->isAndroidType()) {
			return $this->pushMessageAndroid($user, $message, $data);
		}
	}

	public function pushMessageConfirm(Service $service)
	{
		$message 	= "Tu servicio ha sido confirmado";
		$data 		= ['serviceId' => $service->id];

		return $this->pushUserMessage($service->user, $message, $data);
	}
}

/**
 * Facade
 */
class ServiceFacade 
{
	protected $serviceService;
	protected $driverService;
	protected $pushService;

	public function __construct(ServiceService $serviceService, DriverService $driverService, PushService $pushService)
    {
		$this->serviceService = $serviceService;
		$this->driverService = $driverService;
		$this->pushService = $pushService;
    }


	public function confirm(Service $service) 
	{
		$this->serviceService->confirmService($service);
		$this->driverService->disable($drive);
		$this->pushService->pushMessageConfirm($service);
	}	
}


/**
 * Controller
 */
class ServiceController 
{
	protected $facade;

	public function confirm(ConfirmRequest $request, Service $service, Driver $driver) 
	{
		$this->facade->confirm($service, $driver);
		
		return response()->json([
			'error' => '0'
		]);
	}	
}





