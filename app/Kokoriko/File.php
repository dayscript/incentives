<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Entity;
use Storage;
use Log;


class File extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','status','description'];

    public  $connection;
    public  $file;
    public  $files;
    public  $rows;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getFolder( $path = '/' ){
      $this->files = Storage::disk('ftp')->allFiles($path);
      return $this->files;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getContentsFile( $name = null, $parse = null){
      if(is_null($this->name)){
        $this->file = explode(PHP_EOL,Storage::disk('ftp')->get( $name ));
      }else{
        $this->file = explode(PHP_EOL,Storage::disk('ftp')->get( $this->name ));
      }

      $rows = [];
      if( is_null($parse) ){
        return $this->file;
      }
      else{
        foreach($this->file as $line => $contents){
          if(strlen($contents) !== 0){
              $rows[] = explode($parse,$contents);
          }
        }
        $this->rows = $rows;
        return $rows;
      }
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function process($model = null){
        // dd($this->rows);

        switch ($model) {
          case 'entity':
            $file_keys = array('field_no_identificacion','name','mail','field_telephone','fecha_de_registro','cedula_del_asesor','nombre_asesor','line_break');

            foreach ($this->rows as $key => $line) {

              foreach ($line as $row => $value) {
                $new_entity[$file_keys[$row]] = $value;
              }

              $entity = Entity::firstOrCreate(['identification' => $new_entity['field_no_identificacion'], 'name' => $new_entity['name'] ]);
              $entity->subscriptionPoints();
              $entity->createInformation($new_entity);
              $entity->entityInformation;

              Log::info('Enitity Create OK: '.$entity->id);
              try {
                $entity->createRest();
                Log::info('Entity create Drupal OK : '.$entity->identification);

              } catch (\Exception $e) {

                Log::info('Entity exist in Drupal : '.$entity->identification);

              }
              $zoho = $entity->createZoho('Leads');
            }
            break;

          default:
            // code...
            break;
        }
    }
}
