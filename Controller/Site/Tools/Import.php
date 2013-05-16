<?php

	class Controller_Site_Tools_Import extends Abstract_Controller
	{

		public function POST()
		{
			$policy = new Policy_LoggedIn($this->app);
			$policy->ensure();
			$userid = $policy->getData();

			if (!isset($_FILES['file']))
			{
				$this->error("Nothing to do.");
			}

			$file = $_FILES['file'];

			if (isset($file['error']) && $file['error'] > 0)
			{
				$error = $file['error'];
				if ($error == UPLOAD_ERR_NO_FILE)
				{
					$this->error("No file was selected.");
				}
				else if ($error == UPLOAD_ERR_INI_SIZE)
				{
					$this->error("The file you're trying to upload is too big.");
				}
				else
				{
					$this->error("Something went wrong, please try again later.");
				}
			}

			$tmpName = $file['tmp_name'];

			ini_set('auto_detect_line_endings', true);
			$handle = fopen($tmpName,'r');

			$dataLines = array();

			while (($data = fgetcsv($handle)) !== false) {
				$dataLines[] = $data;
			}

			ini_set('auto_detect_line_endings', false);

			if (count($dataLines) < 2)
			{
				$this->error("The file uploaded does not contain enough data to import.");
			}

			$descripData   = $dataLines[0];
			$dateOffset    = false;
			$weightOffset  = false;
			$commentOffset = false;

			for ($i = 0; $i < count($descripData); $i++)
			{
				$field = strtolower(trim($descripData[$i]));
				
				if ($field == "date")
				{
					$dateOffset = $i;
				}
				else if ($field == "weight")
				{
					$weightOffset = $i;
				}
				else if ($field == "comment" || $field == "comments" || $field == "note" || $field == "notes")
				{
					$commentOffset = $i;
				}
			}

			if ($dateOffset === false || $weightOffset === false)
			{
				$this->error("The file uploaded is missing the required fields.");
			}

			$validRows = 0;

			for ($i = 1; $i < count($dataLines); $i++)
			{
				$tmpData = $dataLines[$i];

				$tmpDate    = trim($tmpData[$dateOffset]);
				$tmpWeight  = trim($tmpData[$weightOffset]);
				$tmpComment = '';

				if ($commentOffset && isset($tmpData[$commentOffset]))
				{
					$tmpComment = trim($tmpData[$commentOffset]);
				}

				$tmpWeight = Helper_Weight::validateWeight($tmpWeight);
				$tmpDate   = Helper_Date::validateDate($tmpDate);

				if ($tmpDate && $tmpWeight)
				{
					$mapper = new Mapper_Weight();
					$mapper->addWeight($userid, $tmpWeight, $tmpComment, $tmpDate);

					$validRows++;
				}
			}

			if ($validRows == 0)
			{
				$this->error("No valid data found to import.");
			}

			$this->success("Import complete. $validRows " . (($validRows != 1) ? "rows" : "row") . " were just imported.");
		}

		private function success($string)
		{
			Helper_Message::setSuccess($this->app, $string);
			$this->app->redirect('/tools');
			die();
		}

		private function error($string)
		{
			Helper_Message::setError($this->app, $string);
			$this->app->redirect('/tools');
			die();
		}

	}
