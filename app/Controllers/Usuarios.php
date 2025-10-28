<?php

namespace App\Controllers;

use App\Models\UsuariosModel; 

class Usuarios extends BaseController
{
    protected $usuarios; 
    public function __construct() { 
        $this->usuarios = new UsuariosModel();
    }

    public function index($activo = 1)
    {
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();
        
        $context = ['usuarios'=>$usuarios,
                        'titulo'=>"Usuarios",
                        'pagname'=>"Gestión/Usuarios"];
        
        echo view('panel/header', $context);
        echo view('usuarios/listado', $context);
        echo view('panel/footer');
 
    }
    public function nuevo(){
        $context = ['titulo' => "Nuevo usuario",
                    'pagname' => 'Gestión/Nueva usuario'];

        echo view ('panel/header',$context);
        echo view ('usuarios/nuevo');
        echo view ('panel/footer');
    }
    public function guardar(){
        $hash = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT);
        $this->usuarios->save(
            ['nombre'=>$this->request->getPost('nombre'),
            'apellido'=>$this->request->getPost('apellido'),
            'username'=>$this->request->getPost('username'),
            'clave'=>$hash,
            'dni'=>$this->request->getPost('dni')
            ]
        );
        return redirect()->to(base_url().'public/usuarios/');
        }
        public function borrar($id){
            $this->usuarios->update($id,['activo'=>0]);
            return redirect()->to(base_url().'public/usuarios/');
        }
        public function editar($id){
            $usuario = $this->usuarios->where('id', $id)->findAll();
            $context = ['usuario'=>$usuario,
                    'titulo' => "Edición usuario",
                    'pagname' => 'Gestión/Edición usuario'];

        
            echo view ('panel/header',$context);
            echo view ('usuarios/editar');
            echo view ('panel/footer');
        }
        public function actualizar($id)
{
    
    $data = [
        'nombre' => $this->request->getPost('nombre'),
        'apellido' => $this->request->getPost('apellido'),
        'username' => $this->request->getPost('username'),
        'dni' => $this->request->getPost('dni')
    ];

    
    $clave_nueva = $this->request->getPost('clave');

    //Verificar si se ingresó una nueva contraseña
    if (!empty($clave_nueva)) {
        
        $hash = password_hash($clave_nueva, PASSWORD_DEFAULT);
        $data['clave'] = $hash; 
    }
    

    $this->usuarios->update($id, $data);

    return redirect()->to(base_url() . 'public/usuarios/');
}
      
    }
    
