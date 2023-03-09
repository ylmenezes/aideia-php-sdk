<?php

/**
 * Integração com API a IDEIA
 * 
 * ==========================================================================
 * Obs.: Por padrão a classe vem habilitado para homologação
 * Para usar em modo de produção, passar como parâmetro TRUE para $this->sandbox
 * ==========================================================================
 *
 * Criado em 2023-02-19 
 * @author Yan Menezes  <https://yanmenezes.com.br>
 * @copyright Copyright (c) 2023
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @version	Version 1.0.0
 * 
 */

namespace Aideia;

class Aideia
{
    private $request;
    private $token;

    protected $sandbox;
    protected $email;
    protected $clientID;

    public function __construct(array $options = null, $sandbox = true)
    {
        try {

            if (empty($options)) {
                throw new AideiaException('Opções não foram definidas.', 400);
            }

            if (!isset($options['email'])) {
                throw new AideiaException('E-mail é obrigátorio. array("email" => "example@example.com")', 400);
            }

            if (!isset($options['clientID'])) {
                throw new AideiaException('clientID é obrigátorio. array("clientID" => "23sd8092j$khjldff08923u")', 400);
            }

            $this->sandbox  = $sandbox;
            $this->request  = new Request(['sandbox' => $this->sandbox]);
            $this->email    = $options['email'];
            $this->clientID = $options['clientID'];
            $this->token    = $this->auth();
        } catch (AideiaException $e) {
            die($e);
        }
    }

    /**
     * Endpoind de listar os produtos
     * @return array
     */
    public function getProdutos(): array
    {

        $oProdutos = $this->request->send('getProdutos', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ]
        ]);

        return $oProdutos->data;
    }

    /**
     * Endpoind de detalhar os produtos
     * @return object
     */
    public function getProduto($uid): object
    {

        return $this->request->send('detailProduto', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind de atualizar o produto
     * @return object
     */
    public function updateProduto($uid, $data): object
    {

        return $this->request->send('updateProduto', $data, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind para cadastrar um novo produto
     * @return object
     */
    public function createProduto($data): object
    {
        return $this->request->send('createProduto', $data, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ]
        ]);
    }

    /**
     * Endpoind para mudar o status do produto
     * @return object
     */
    public function deleteProduto($uid): object
    {
        return $this->request->send('deleteProduto', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind para listar as categorias 
     * @return object
     */
    public function getCategorias(): object
    {
        return $this->request->send('getCategorias', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ]
        ]);
    }

    /**
     * Endpoind para criar as categorias
     * @return object
     */
    public function updateCategoria($uid, $data): object
    {
        return $this->request->send('updateCategoria', $data, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind para criar as categorias
     * @return object
     */
    public function createCategoria($data): object
    {
        return $this->request->send('createCategoria', $data, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ]
        ]);
    }

    /**
     * Endpoind para criar as imagens
     * @return object
     */
    public function createImagens($data): object
    {
        return $this->request->send('createImagens', $data, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ]
        ]);
    }

    /**
     * Endpoind para excluir as imagens
     * @return object
     */
    public function deleteImagens($uid): object
    {
        return $this->request->send('deleteImagens', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind para detalhar as imagens
     * @return object
     */
    public function detailImagem($uid): object
    {
        return $this->request->send('detailImagem', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * Endpoind para listar as imagens de um produto
     * @return object
     */
    public function getImagemProduto($uid): object
    {
        return $this->request->send('getImagemProduto', null, [
            'headers' => [
                "Authorization:Bearer $this->token",
            ],
            'params' => [
                'uid' => $uid
            ]
        ]);
    }

    /**
     * retorna o token de autenticação das requisições
     * @return string 
     */
    private function auth(): string
    {
        try {
            $oAuth = $this->request->send('auth', ["email" =>  $this->email,  "clientID" => $this->clientID]);

            return $oAuth->token;
        } catch (\Exception $e) {
            throw new AideiaException($e, $e->getCode());
        }
    }
}
