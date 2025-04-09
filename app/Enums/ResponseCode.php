<?php

namespace App\Enums;

use ArchTech\Enums\From;
use ArchTech\Enums\Values;
use ArchTech\Enums\Options;
use ArchTech\Enums\InvokableCases;
use Symfony\Component\HttpFoundation\Response;

enum ResponseCode
{
    use From;
    use Values;
    use Options;
    use InvokableCases;

    case SUCCESS;
    case ERR_VALIDATION;
    case ERR_AUTHENTICATION;
    case ERR_INVALID_IP_ADDRESS;
    case ERR_MISSING_SIGNATURE_HEADER;
    case ERR_INVALID_SIGNATURE_HEADER;
    case ERR_INVALID_OPERATION;
    case ERR_ENTITY_NOT_FOUND;
    case ERR_ROUTE_NOT_FOUND;
    case ERR_UNKNOWN;
    case ERR_FORBIDDEN_ACCESS;
    case ERR_METHOD_NOT_IMPLEMENTED;
    case ERR_ACTION_UNAUTHORIZED;
    case ERR_INSUFFICIENT_BALANCE;
    case ERR_INVALID_ACTION;
    case ERR_UNIQUE_RECORD;
    case ERR_RECORD_CONSTRAINT;
    case ERR_QUERY_EXCEPTION;

    // IPG
    case BILLER_AUTHENTICATION;
    case BILLER_INVALID_TRANSACTION;
    case BILLER_INVALID_TRANSACTION_AMOUNT;
    case BILLER_BILL_INVALID_AMOUNT;
    case BILLER_INVALID_VA_NUMBER;
    case BILLER_BILL_ALREADY_PAID;
    case BILLER_BILL_NOT_FOUND;
    case BILLER_PAYMENT_NOT_FOUND;
    case BILLER_PAYMENT_ALREADY_REVERSED;
    case BILLER_SUSPECT_TRANSACTION;
    case BILLER_GATEWAY_INVALID_RESPONSE;

    case ERR_CANNOT_DELETE_USER;

    /**
     * Determine httpCode from response code.
     *
     * @return int
     */
    public function httpCode(): int
    {
        return match ($this) {
            self::SUCCESS => Response::HTTP_OK,

            self::ERR_MISSING_SIGNATURE_HEADER,
            self::ERR_INVALID_SIGNATURE_HEADER,
            self::ERR_INVALID_IP_ADDRESS,
            self::ERR_AUTHENTICATION => Response::HTTP_UNAUTHORIZED,

            self::ERR_ENTITY_NOT_FOUND,
            self::ERR_VALIDATION,
            self::ERR_UNIQUE_RECORD => Response::HTTP_UNPROCESSABLE_ENTITY,


            self::ERR_INVALID_OPERATION,
            self::ERR_ROUTE_NOT_FOUND => Response::HTTP_NOT_FOUND,

            self::ERR_UNKNOWN,
            self::ERR_QUERY_EXCEPTION,
            self::ERR_METHOD_NOT_IMPLEMENTED => Response::HTTP_INTERNAL_SERVER_ERROR,

            self::ERR_FORBIDDEN_ACCESS,
            self::ERR_ACTION_UNAUTHORIZED => Response::HTTP_FORBIDDEN,

            default => Response::HTTP_BAD_REQUEST
        };
    }

    /**
     * Set error to readable message string.
     *
     * @return string
     */
    public function message(): string
    {
        return ucwords(strtolower(str_replace(['ERR_', '_'], ['', ' '], $this->name)));
    }
}
