<?php
echo json_encode(DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'pago'"));
