<?php

namespace GlobsTracking\Globs;

class Helper
{
    public function __construct()
    {
    }

    public function displayErrorMessage($message)
    {
        return "<div style=\"padding: 15px; margin-bottom: 20px; border: 1px solid #ebccd1;
border-radius: 4px; color: #a94442; background-color: #f2dede;\">" . $message . "</div>";
    }

    public function displaySuccessMessage($message)
    {
        return "<div style=\"padding: 15px; margin-bottom: 20px; border: 1px solid #d6e9c6;
border-radius: 4px; color: #3c763d; background-color: #dff0d8;\">" . $message . "</div>";
    }

    public function displayInfoMessage($message)
    {
        return "<div style=\"padding: 15px; margin-bottom: 20px; border: 1px solid #bce8f1;
border-radius: 4px; color: #31708f; background-color: #d9edf7;\">" . $message . "</div>";
    }

    public function displayWarningMessage($message)
    {
        return "<div style=\"padding: 15px; margin-bottom: 20px; border: 1px solid #faebcc;
border-radius: 4px; color: #8a6d3b; background-color: #fcf8e3;\">" . $message . "</div>";
    }
}