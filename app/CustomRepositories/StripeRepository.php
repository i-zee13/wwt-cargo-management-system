<?php

namespace App\CustomRepositories;

use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
use Stripe\StripeClient;

class StripeRepository
{
    protected $api_key;
    protected $secret_key;
    protected $client;
    public function __construct()
    {
        $this->getCredential();
        $this->client   =   new StripeClient($this->secret_key);
    }

    public function GetCheckoutSessions()
    {
        $Result =   new \stdClass();
        try {
            $objResponse        =   $this->client->checkout->sessions->all(['limit' => 2]);
            $Result->status     =   0;
            $Result->message    =   'Success';
            $Result->data       =   $objResponse;
        } catch(CardException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getError()->message;
            $Result->data       =   null;
        } catch (RateLimitException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (InvalidRequestException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (AuthenticationException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiConnectionException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiErrorException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (\Exception $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        }
        return $Result;
    }

    public function CreateCheckoutSession($data)
    {
        $Result =   new \stdClass();
        try {
            $objResponse        =   $this->client->checkout->sessions->create($data);
            $Result->status     =   0;
            $Result->message    =   'Success';
            $Result->data       =   $objResponse;
        } catch(CardException $e) {
            /*// Since it's a decline, \Stripe\Exception\CardException will be caught
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';*/
            $Result->status     =   0;
            $Result->message    =   $e->getError()->message;
            $Result->data       =   null;
        } catch (RateLimitException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (InvalidRequestException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (AuthenticationException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiConnectionException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiErrorException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (\Exception $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        }
        return $Result;
    }

    public function ExpireCheckoutSession($id)
    {
        $Result =   new \stdClass();
        try {
            $objResponse        =   $this->client->checkout->sessions->expire($id, []);
            $Result->status     =   0;
            $Result->message    =   'Success';
            $Result->data       =   $objResponse;
        } catch(CardException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getError()->message;
            $Result->data       =   null;
        } catch (RateLimitException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (InvalidRequestException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (AuthenticationException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiConnectionException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiErrorException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (\Exception $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        }
        return $Result;
    }

    public function GetCheckoutSession($id)
    {
        $Result =   new \stdClass();
        try {
            $objResponse        =   $this->client->checkout->sessions->retrieve($id);
            $Result->status     =   0;
            $Result->message    =   'Success';
            $Result->data       =   $objResponse;
        } catch(CardException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getError()->message;
            $Result->data       =   null;
        } catch (RateLimitException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (InvalidRequestException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (AuthenticationException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiConnectionException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (ApiErrorException $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        } catch (\Exception $e) {
            $Result->status     =   0;
            $Result->message    =   $e->getMessage();
            $Result->data       =   null;
        }
        return $Result;
    }

    protected function getCredential()
    {
        $setting    =   array();
        $credential =   DB::table('integrations')->where('type', '=', 'stripe')->where('status', '=', 'active')->first();
        if (isset($credential->setting) && $credential->setting != '')
        {
            $mode       =   isset($credential->mode) ? $credential->mode : '';
            $settings   =   json_decode($credential->setting, true);
            $setting    =   isset($settings[$mode]) ? $settings[$mode] : array();
        }
        $this->api_key      =   isset($setting['api_key']) ? $setting['api_key'] : '';
        $this->secret_key   =   isset($setting['secret_key']) ? $setting['secret_key'] : '';
    }
}